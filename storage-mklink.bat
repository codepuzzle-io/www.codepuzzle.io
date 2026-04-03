@echo off

net session >nul 2>&1
if %errorLevel% neq 0 (
    powershell -Command "Start-Process cmd -ArgumentList '/c %~s0' -Verb runAs"
    exit
)

cd /d %~dp0

if exist public\storage (
    rmdir public\storage
)

mklink /J public\storage storage\app\public

echo Lien cree !
pause