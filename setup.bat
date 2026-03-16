@echo off
echo ===================================================
echo   INSTALANDO DEPENDENCIAS DE LOS MICROSERVICIOS
echo ===================================================
echo.

echo [1/3] Instalando dependencias de Ventas (Express / Node.js)...
cd sales-express
call npm install
cd ..
echo OK - Express listo.
echo.

echo [2/3] Instalando dependencias del Api Gateway (Laravel / PHP)...
cd api-gateway-laravel
call composer install
cd ..
echo OK - Laravel listo.
echo.

echo [3/3] Instalando dependencias de Inventario (Flask / Python)...
cd inventory-flask
if not exist venv (
    echo Creando entorno virtual de Python...
    python -m venv venv
)
call venv\Scripts\activate.bat
pip install Flask flask-cors firebase-admin
deactivate
cd ..
echo OK - Flask listo.
echo.

echo ===================================================
echo INSTALACION COMPLETADA SACTISFACTORIAMENTE.
echo Para arrancar todos los servidores, ejecuta: start.bat
echo ===================================================
pause
