#!/usr/bin/env python3
"""Prepare doc-site/docs from repository Markdown; set GHP_REPO_URL for blob links."""
from __future__ import annotations

import os
import shutil
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parent.parent
OUT_DIR = Path(__file__).resolve().parent / "docs"
LEGACY_SUBDIR = "legacy-html"
REPO_URL = os.environ.get("GHP_REPO_URL", "https://github.com/example/loganalyzer").rstrip("/")
DEFAULT_BRANCH = os.environ.get("GHP_DEFAULT_BRANCH", "master").strip() or "master"


def main() -> int:
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    agents = REPO_ROOT / "AGENTS.md"
    if agents.is_file():
        shutil.copyfile(agents, OUT_DIR / "docker.md")
        print(f"Wrote {OUT_DIR / 'docker.md'}")
    readme = REPO_ROOT / "README.md"
    if readme.is_file():
        text = readme.read_text(encoding="utf-8", errors="replace")
        (OUT_DIR / "project-readme.md").write_text(text, encoding="utf-8", newline="\n")
        print(f"Wrote {OUT_DIR / 'project-readme.md'}")
    manuals = REPO_ROOT / "doc"
    if manuals.is_dir():
        legacy_out = OUT_DIR / LEGACY_SUBDIR
        if legacy_out.is_dir():
            shutil.rmtree(legacy_out)
        legacy_out.mkdir(parents=True, exist_ok=True)
        html_files = sorted(manuals.glob("*.html"))
        for src in html_files:
            shutil.copyfile(src, legacy_out / src.name)
            print(f"Copied {src.name} -> {legacy_out / src.name}")

        blob_base = f"{REPO_URL}/blob/{DEFAULT_BRANCH}/doc"
        rel_prefix = f"{LEGACY_SUBDIR}/"

        lines = [
            "# Legacy HTML manuals",
            "",
            "These pages are **legacy HTML 4** manuals from the upstream tree. They are "
            "**included in this handbook** so you can read them without leaving the site. "
            "For Docker, CI, and contributor notes, use the other sections in the left nav.",
            "",
            "## Pages (in-site)",
            "",
        ]
        for f in html_files:
            name = f.name
            title = name.replace(".html", "").replace("_", " ")
            lines.append(f"- [{title}]({rel_prefix}{name}) (`{name}`)")
        lines.extend(
            [
                "",
                "## Source in the repository",
                "",
                f"Original files live under [`doc/`]({blob_base}/) on branch `{DEFAULT_BRANCH}`.",
                "",
            ]
        )
        (OUT_DIR / "legacy-html-manuals.md").write_text(
            "\n".join(lines) + "\n",
            encoding="utf-8",
            newline="\n",
        )
        print(f"Wrote {OUT_DIR / 'legacy-html-manuals.md'}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
