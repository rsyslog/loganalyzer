#!/usr/bin/env python3
"""Prepare doc-site/docs from repository Markdown; embed upstream doc/*.html as MkDocs pages under user-guide/chapters/."""
from __future__ import annotations

import os
import re
import shutil
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parent.parent
OUT_DIR = Path(__file__).resolve().parent / "docs"
# Handbook-native pages live in user-guide/; imported doc/*.html render under user-guide/chapters/.
USER_GUIDE_DIR = "user-guide"
IMPORTED_CHAPTERS_DIR = "chapters"
OVERVIEW_MD = "overview.md"
REPO_URL = os.environ.get("GHP_REPO_URL", "https://github.com/rsyslog/loganalyzer").rstrip("/")
DEFAULT_BRANCH = os.environ.get("GHP_DEFAULT_BRANCH", "master").strip() or "master"

HREF_RE = re.compile(r"""(?is)\bhref\s*=\s*(["'])([^"']*)\1""")
TITLE_RE = re.compile(r"""(?is)<title[^>]*>([^<]*)</title>""")
BODY_RE = re.compile(r"""(?is)<body[^>]*>(.*?)</body>""")
FIRST_H1_RE = re.compile(r"""(?is)^\s*<h1[^>]*>.*?</h1>\s*""")
LEGACY_NAV_LINE = re.compile(r"^(\s*)-\s")

# free_support.html links to a non-existent filename
STEM_ALIASES = {"professional_support": "professional_services"}

# Preferred left-nav order; any new doc/*.html not listed here is appended (sorted).
LEGACY_NAV_ORDER = [
    "manual",
    "basics",
    "install",
    "searching",
    "troubleshoot",
    "build_from_repo",
    "free_support",
    "professional_services",
    "textfiles",
    "windowsevent",
]

# Left-nav labels (override stem-derived title)
LEGACY_NAV_LABELS = {
    "manual": "Documentation home",
    "basics": "Basics",
    "install": "Installation",
    "searching": "Search syntax",
    "troubleshoot": "Troubleshooting",
    "build_from_repo": "Build from repo",
    "free_support": "Free support",
    "professional_services": "Professional services",
    "textfiles": "Text log files",
    "windowsevent": "Windows Event Log",
}

MKDOCS_PATH = Path(__file__).resolve().parent / "mkdocs.yml"
# mkdocs.yml must contain this exact nav header line; `_patch_mkdocs_legacy_nav` replaces this section.
LEGACY_NAV_HEADER = "  - LogAnalyzer user guide:"


def _yaml_single_quoted(value: str) -> str:
    """Single-quoted YAML scalar; double internal apostrophes per YAML 1.1/1.2."""
    return "'" + value.replace("'", "''") + "'"


def _nav_item_indent(line: str) -> int | None:
    m = LEGACY_NAV_LINE.match(line)
    return len(m.group(1)) if m else None


def _extract_title_and_body(html: str) -> tuple[str, str]:
    tm = TITLE_RE.search(html)
    title = (tm.group(1).strip() if tm else "User guide").replace("\n", " ")
    bm = BODY_RE.search(html)
    body = bm.group(1).strip() if bm else html
    body = FIRST_H1_RE.sub("", body, count=1)
    return title, body


def _rewrite_hrefs(fragment: str, stems: set[str]) -> str:
    lower_to_canon = {s.lower(): s for s in stems}

    def repl(m: re.Match[str]) -> str:
        quote, url = m.group(1), m.group(2)
        lower = url.lower()
        if lower.startswith(("http://", "https://", "mailto:", "#", "//")):
            return m.group(0)
        base, _, frag = url.partition("#")
        frag_q = f"#{frag}" if frag else ""
        if not base:
            return m.group(0)

        stem: str | None = None
        if base.lower().endswith(".html"):
            stem = base.rsplit(".", 1)[0]
        elif "." not in base:
            stem = base

        if stem is None:
            return m.group(0)

        stem_l = stem.lower()
        stem_l = STEM_ALIASES.get(stem_l, stem_l)
        canon = lower_to_canon.get(stem_l)
        if canon:
            return f"href={quote}../{canon}/{frag_q}{quote}"
        if base.lower().endswith(".html"):
            return f"href={quote}../{stem}/{frag_q}{quote}"
        return m.group(0)

    return HREF_RE.sub(repl, fragment)


def _ordered_legacy_stems(stems: set[str]) -> list[str]:
    seen: set[str] = set()
    out: list[str] = []
    for s in LEGACY_NAV_ORDER:
        if s in stems:
            out.append(s)
            seen.add(s)
    out.extend(sorted(stems - seen))
    return out


def _format_legacy_nav_lines(stems_ordered: list[str]) -> list[str]:
    lines = [
        LEGACY_NAV_HEADER,
        f"    - {_yaml_single_quoted('Overview')}: {USER_GUIDE_DIR}/{OVERVIEW_MD}",
        f"    - {_yaml_single_quoted('Quick start')}: {USER_GUIDE_DIR}/quick-start.md",
        f"    - {_yaml_single_quoted('Interface map')}: {USER_GUIDE_DIR}/interface-map.md",
    ]
    for stem in stems_ordered:
        label = LEGACY_NAV_LABELS.get(stem, stem.replace("_", " ").title())
        lines.append(
            f"    - {_yaml_single_quoted(label)}: {USER_GUIDE_DIR}/{IMPORTED_CHAPTERS_DIR}/{stem}.md"
        )
    return lines


def _patch_mkdocs_legacy_nav(stems_ordered: list[str]) -> None:
    text = MKDOCS_PATH.read_text(encoding="utf-8", errors="replace")
    file_lines = text.splitlines()
    start_i: int | None = None
    for i, line in enumerate(file_lines):
        if line == LEGACY_NAV_HEADER:
            start_i = i
            break
    if start_i is None:
        raise SystemExit(f"{MKDOCS_PATH}: missing {LEGACY_NAV_HEADER!r} nav section")
    header_indent = _nav_item_indent(file_lines[start_i])
    if header_indent is None:
        raise SystemExit(f"{MKDOCS_PATH}: legacy nav header is not a valid list item")
    end_i = start_i + 1
    while end_i < len(file_lines):
        line = file_lines[end_i]
        if not line.strip():
            break
        child_indent = _nav_item_indent(line)
        if child_indent is None or child_indent <= header_indent:
            break
        end_i += 1
    new_file_lines = (
        file_lines[:start_i] + _format_legacy_nav_lines(stems_ordered) + file_lines[end_i:]
    )
    MKDOCS_PATH.write_text("\n".join(new_file_lines) + "\n", encoding="utf-8", newline="\n")
    print(f"Updated {MKDOCS_PATH.name} user guide nav ({len(stems_ordered)} imported chapter(s))")


def _write_legacy_markdown(
    out_path: Path,
    title: str,
    body_html: str,
) -> None:
    md = (
        "---\n"
        f"title: {_yaml_single_quoted(title)}\n"
        "---\n\n"
        '<div class="legacy-html-doc">\n\n'
        f"{body_html}\n\n"
        "</div>\n"
    )
    out_path.write_text(md, encoding="utf-8", newline="\n")


def _remove_pre_migration_doc_artifacts(out_dir: Path) -> None:
    """Remove handbook output from the pre-PR layout (legacy-html/, legacy-html-manuals.md)."""
    for name in ("legacy-html", "legacy-html-manuals.md"):
        path = out_dir / name
        if path.is_dir():
            shutil.rmtree(path)
        elif path.is_file():
            path.unlink()


def main() -> int:
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    # Handbook Docker lives in docker.md (overview) plus docker-install.md / docker-develop.md;
    # do not overwrite — AGENTS.md remains IDE-oriented in the repo root.
    readme = REPO_ROOT / "README.md"
    if readme.is_file():
        text = readme.read_text(encoding="utf-8", errors="replace")
        (OUT_DIR / "project-readme.md").write_text(text, encoding="utf-8", newline="\n")
        print(f"Wrote {OUT_DIR / 'project-readme.md'}")
    _remove_pre_migration_doc_artifacts(OUT_DIR)
    manuals = REPO_ROOT / "doc"
    if manuals.is_dir():
        guide_root = OUT_DIR / USER_GUIDE_DIR
        guide_root.mkdir(parents=True, exist_ok=True)
        chapters_out = guide_root / IMPORTED_CHAPTERS_DIR
        if chapters_out.is_dir():
            shutil.rmtree(chapters_out)
        chapters_out.mkdir(parents=True, exist_ok=True)

        html_files = sorted(manuals.glob("*.html"))
        stems = {f.stem for f in html_files}

        for src in html_files:
            raw = src.read_text(encoding="utf-8", errors="replace")
            doc_title, body = _extract_title_and_body(raw)
            body = _rewrite_hrefs(body, stems)
            md_name = src.with_suffix(".md").name
            _write_legacy_markdown(chapters_out / md_name, doc_title, body)
            print(f"Wrote {chapters_out / md_name} <- {src.name}")

        blob_base = f"{REPO_URL}/blob/{DEFAULT_BRANCH}/doc"
        rel_prefix = f"{IMPORTED_CHAPTERS_DIR}/"

        ordered = _ordered_legacy_stems(stems)
        stem_to_html = {f.stem: f.name for f in html_files}
        lines = [
            "# LogAnalyzer user guide (overview)",
            "",
            "This section is the **LogAnalyzer user guide**: how the app works, installation, search, "
            "and operations. **Quick start** and **Interface map** are handbook-native pages (with "
            "screenshots where available). The chapters below are **imported** from the upstream "
            "`doc/*.html` manuals in this repository, rendered here with the same navigation and theme "
            "as the rest of the site. For Docker install versus development/CI, use **Docker** "
            "in the left nav.",
            "",
            f"Every `*.html` manual under [`doc/`]({blob_base}/) is listed below "
            f"**({len(ordered)} chapters)**; matching pages appear under "
            "**LogAnalyzer user guide** in the sidebar.",
            "",
            "## Pages (in-site)",
            "",
        ]
        for stem in ordered:
            label = LEGACY_NAV_LABELS.get(stem, stem.replace("_", " ").title())
            html_name = stem_to_html[stem]
            lines.append(f"- [{label}]({rel_prefix}{stem}.md) (`{html_name}`)")
        lines.extend(
            [
                "",
                "## Source in the repository",
                "",
                f"Original files live under [`doc/`]({blob_base}/) on branch `{DEFAULT_BRANCH}`.",
                "",
            ]
        )
        overview_path = OUT_DIR / USER_GUIDE_DIR / OVERVIEW_MD
        overview_path.write_text("\n".join(lines) + "\n", encoding="utf-8", newline="\n")
        print(f"Wrote {overview_path}")
        _patch_mkdocs_legacy_nav(_ordered_legacy_stems(stems))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
