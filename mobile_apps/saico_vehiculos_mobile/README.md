# SAICO Vehiculos Mobile

Aplicacion Flutter aislada del Laravel principal para operar:

- solicitud de vehiculos
- checklist de salida
- checklist de entrada

## Estructura

- `lib/app`
  Configuracion global y rutas.
- `lib/core`
  Tema, red, constantes y layout adaptativo.
- `lib/features/auth`
  Acceso y sesion local.
- `lib/features/vehicle_requests`
  Solicitudes y listado de salidas.
- `lib/features/checklists`
  Formularios responsivos de salida y entrada.

## Criterios de arquitectura

- Organizacion por features.
- Riverpod para estado.
- GoRouter para navegacion.
- Dio para API.
- UI responsive por breakpoints.
- Capa Laravel movil separada bajo `/api/mobile/v1`.

## Siguiente integracion recomendada

1. Configurar `ApiEndpoints.baseUrl`.
2. Implementar repositorios multipart para checklists.
3. Persistir sesion con `flutter_secure_storage`.
4. Agregar pruebas de widgets y golden tests.
