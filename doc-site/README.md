# LogAnalyzer documentation site

Build locally:

```bash
pip install -r doc-site/requirements.txt
python doc-site/prepare_docs.py
python -m mkdocs build -f doc-site/mkdocs.yml
```

For GitHub Pages, set `GHP_REPO_URL=https://github.com/OWNER/REPO` when running `prepare_docs.py` (the workflow does this automatically). Optional: `GHP_DEFAULT_BRANCH` (default `master`) controls the link to `doc/` in the hub page.

The **LogAnalyzer user guide** block in `doc-site/mkdocs.yml` is maintained by `prepare_docs.py`: the script rewrites the list of chapters imported from every `doc/*.html`, and keeps fixed entries (Overview, Quick start, Interface map) at the top of that section when you regenerate docs.
