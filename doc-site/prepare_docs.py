#!/usr/bin/env python3
"""Prepare doc-site/docs from repository Markdown; set GHP_REPO_URL for blob links."""
from __future__ import annotations

import os
import shutil
import sys
from pathlib import Path

REPO_ROOT = Path(__file__).resolve().parent.parent
OUT_DIR = Path(__file__).resolve().parent / "docs"
REPO_URL = os.environ.get("GHP_REPO_URL", "https://github.com/example/loganalyzer").rstrip("/")


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
        blob = f"{REPO_URL}/blob/main/doc"
        (OUT_DIR / "legacy-html-manuals.md").write_text(
            "# Legacy HTML manuals\n\n"
            "The upstream-style HTML manuals live in the repository under "
            f"[`doc/`]({blob}/).\n\n"
            "- [manual.html](" + blob + "/manual.html)\n"
            "- [install.html](" + blob + "/install.html)\n"
            "- [searching.html](" + blob + "/searching.html)\n"
            "- [troubleshoot.html](" + blob + "/troubleshoot.html)\n",
            encoding="utf-8",
            newline="\n",
        )
        print(f"Wrote {OUT_DIR / 'legacy-html-manuals.md'}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
