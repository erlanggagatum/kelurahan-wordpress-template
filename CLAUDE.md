@AGENTS.md

## Claude Code specifics

- Works on Windows, macOS and Linux. Use forward slashes and repo-relative paths in commands and docs.
- Run `php bin/check.php` after every change set; run `php bin/check.php --http` before
  reporting a task done if `.wp/` exists and the server is up.
- Check `.wp/wp-content/debug.log` after loading pages — PHP warnings land there, not on screen.
