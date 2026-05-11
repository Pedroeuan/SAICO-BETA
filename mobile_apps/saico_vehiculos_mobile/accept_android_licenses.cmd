@echo off
setlocal

set "FLUTTER_HOME=%USERPROFILE%\development\flutter"
set "ANDROID_SDK_ROOT=%USERPROFILE%\AppData\Local\Android\Sdk"
set "JAVA_HOME=%USERPROFILE%\development\jdk17"
set "PATH=%JAVA_HOME%\bin;%FLUTTER_HOME%\bin;%ANDROID_SDK_ROOT%\platform-tools;%ANDROID_SDK_ROOT%\cmdline-tools\latest\bin;%PATH%"
set "LOG_FILE=%~dp0accept_android_licenses.log"

echo ============================================
echo Aceptacion de licencias Android
echo ============================================
echo.
echo Guardando log en:
echo %LOG_FILE%
echo.
echo Cuando aparezcan preguntas, escribe y y presiona Enter.
echo Repite hasta terminar.
echo.
call flutter doctor --android-licenses >> "%LOG_FILE%" 2>&1
set "STEP_ERROR=%ERRORLEVEL%"
echo.
echo Resultado de licencias: %STEP_ERROR%
echo.
call flutter doctor >> "%LOG_FILE%" 2>&1
set "DOCTOR_ERROR=%ERRORLEVEL%"
echo.
echo Resultado de flutter doctor: %DOCTOR_ERROR%
echo.
echo Si algo fallo, abre este archivo:
echo %LOG_FILE%
echo.
pause
