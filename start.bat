@echo off
echo ===================================================
echo   INICIANDO LOS 3 MICROSERVICIOS EN PARALELO...
echo ===================================================
echo Se abriran 3 ventanas nuevas. No las cierres mientras pruebas el sistema.
echo.

echo Iniciando Express (Ventas) en puerto 3000...
start "Microservicio Ventas (Express)" cmd /k "cd sales-express && node index.js"

echo Iniciando Flask (Inventario) en puerto 5000...
start "Microservicio Inventario (Flask)" cmd /k "cd inventory-flask && call venv\Scripts\activate.bat && python app.py"

echo Iniciando Gateway (Laravel) en puerto 8000...
start "API Gateway (Laravel)" cmd /k "cd api-gateway-laravel && php artisan serve --port=8000"

echo.
echo Todos los servicios han sido lanzados.
echo Revisa las nuevas ventanas que se abrieron para confirmar que no hay errores.
pause
