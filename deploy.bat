rem php floyer deploy --ansi

rem push code

git status
git add .
git commit -am "updated"
git push

rem deploy on server

curl https://elogger.eteamprojects.com/deploy

pause
