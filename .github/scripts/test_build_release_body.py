#!/usr/bin/env python3
"""Regression tests for extracting release notes from the root ChangeLog."""

import unittest

from build_release_body import extract_changelog_block


class ExtractChangelogBlockTests(unittest.TestCase):
    def test_skips_heading_separator_and_stops_at_next_release(self):
        changelog = "\n".join(
            [
                "--------------------------------------------------------------------------------",
                "Version 5.0.2, 2026-09-28",
                "--------------------------------------------------------------------------------",
                "- Security fixes & hardening",
                "  - Reporter credit: Thanks to @KHr00t.",
                "--------------------------------------------------------------------------------",
                "Version 5.0.1, 2026-05-04",
                "--------------------------------------------------------------------------------",
                "- Older changes",
            ]
        )

        result = extract_changelog_block(changelog, "5.0.2")

        self.assertIn("- Security fixes & hardening", result)
        self.assertIn("@KHr00t", result)
        self.assertNotIn("Version 5.0.1", result)
        self.assertNotIn("--------------------------------------------------------------------------------", result)

    def test_returns_empty_for_missing_or_invalid_version(self):
        changelog = "Version 5.0.2, 2026-09-28\n- Notes\n"

        self.assertEqual("", extract_changelog_block(changelog, "5.0.3"))
        self.assertEqual("", extract_changelog_block(changelog, "5.0.2/extra"))


if __name__ == "__main__":
    unittest.main()
