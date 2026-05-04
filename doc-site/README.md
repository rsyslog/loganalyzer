# LogAnalyzer documentation site

Build locally:

```bash
pip install -r doc-site/requirements.txt
python doc-site/prepare_docs.py
python -m mkdocs build -f doc-site/mkdocs.yml
```

For GitHub Pages, set `GHP_REPO_URL=https://github.com/OWNER/REPO` when running `prepare_docs.py` (the workflow does this automatically).
