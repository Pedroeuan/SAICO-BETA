@echo off
setlocal

set "FLUTTER_HOME=%USERPROFILE%\development\flutter"
set "ANDROID_SDK_ROOT=%USERPROFILE%\AppData\Local\Android\Sdk"
set "JAVA_HOME=%USERPROFILE%\development\jdk17"
set "PATH=%JAVA_HOME%\bin;%FLUTTER_HOME%\bin;%ANDROID_SDK_ROOT%\platform-tools;%ANDROID_SDK_ROOT%\cmdline-tools\latest\bin;%PATH%"
set "LOG_FILE=%~dp0run_on_android.log"
set "PROJECT_ROOT=%~dp0..\.."
set "DEVICE_ID=%~1"

if "%DEVICE_ID%"=="" (
  for /f "skip=1 tokens=1" %%D in ('adb devices') do (
    if not "%%D"=="" if not "%%D"=="List" if "%DEVICE_ID%"=="" set "DEVICE_ID=%%D"
  )
)

if "%DEVICE_ID%"=="" (
  echo No se detecto ningun dispositivo Android por cable.
  echo Conecta el celular, activa depuracion USB y vuelve a intentar.
  pause
  exit /b 1
)

echo ============================================
echo SAICO Vehiculos Mobile - Android real por cable
echo ============================================
echo.
echo Guardando log en:
echo %LOG_FILE%
echo.
echo Dispositivo configurado:
echo %DEVICE_ID%
echo.
echo 0. Levantando API Laravel real...
start "SAICO Mobile API" /min cmd /c "cd /d %PROJECT_ROOT% && php artisan serve --host=127.0.0.1 --port=8000 >> mobile_apps\saico_vehiculos_mobile\mobile_api.log 2>&1"
timeout /t 4 >nul
echo Resultado: %ERRORLEVEL%
echo.
echo 1. Verificando Flutter...
call flutter --version >> "%LOG_FILE%" 2>&1
echo Resultado: %ERRORLEVEL%
echo.
echo 2. Dispositivos detectados...
call flutter devices >> "%LOG_FILE%" 2>&1
echo Resultado: %ERRORLEVEL%
echo.
echo 3. Enlazando celular por cable a la API local...
call adb reverse tcp:8000 tcp:8000 >> "%LOG_FILE%" 2>&1
echo Resultado: %ERRORLEVEL%
echo.
echo 4. Ejecutando la app real en Android sin pub get...
call flutter run --no-pub -d %DEVICE_ID% --dart-define=API_BASE_URL=http://127.0.0.1:8000/api/mobile/v1 --dart-define=USE_MOCK_DATA=false >> "%LOG_FILE%" 2>&1
echo Resultado final: %ERRORLEVEL%
echo.
echo Si algo fallo, abre este archivo:
echo %LOG_FILE%
echo.
pause
