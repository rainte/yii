# template

Template

```bash
git remote add template git@github.com:rainte/template.git
git fetch template
git merge template/main --allow-unrelated-histories
git checkout --ours -- .
git add -A
git commit -m "commit"
git push origin main
```
