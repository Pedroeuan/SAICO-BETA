# Modulo de Publicaciones: Facebook, Instagram y LinkedIn

## Alcance actual

- `facebook`: soporta texto e imagen
- `instagram`: soporta imagen con caption
- `linkedin`: soporta texto e imagen
- `x/twitter`: eliminado del flujo activo

## Variables a configurar

Archivo: `scripts-python/.env`

```dotenv
DB_HOST=127.0.0.1
DB_USER=root
DB_PASS=
DB_NAME=saico_beta
APP_URL=http://127.0.0.1:8000

FACEBOOK_PAGE_TOKEN=
FACEBOOK_PAGE_ID=

INSTAGRAM_ACCESS_TOKEN=
INSTAGRAM_IG_USER_ID=

LINKEDIN_ACCESS_TOKEN=
LINKEDIN_ORG_ID=
LINKEDIN_PERSON_URN=
```

## Facebook

### Requisitos

- App de Meta con Facebook Login
- Permisos de pagina aprobados o disponibles para tu modo de uso
- Pagina de Facebook administrada por la cuenta que autoriza

### Flujo recomendado

1. Obtener `short-lived user access token` desde Graph API Explorer.
2. Intercambiarlo por `long-lived user access token`.
3. Obtener el `page access token` de la pagina.
4. Guardar:
   - `FACEBOOK_PAGE_TOKEN`
   - `FACEBOOK_PAGE_ID`

### Exchange a long-lived token

```text
GET https://graph.facebook.com/v19.0/oauth/access_token
    ?grant_type=fb_exchange_token
    &client_id={APP_ID}
    &client_secret={APP_SECRET}
    &fb_exchange_token={SHORT_LIVED_USER_TOKEN}
```

### Obtener paginas administradas y su token

```text
GET https://graph.facebook.com/v19.0/me/accounts?access_token={LONG_LIVED_USER_TOKEN}
```

De la respuesta toma:

- `id` de la pagina
- `access_token` de la pagina

## Instagram

### Requisitos

- Cuenta `Instagram Business` o `Creator`
- Vinculada a una pagina de Facebook
- Permisos de Meta para publicar contenido
- La imagen debe estar disponible por URL publica

### Flujo recomendado

1. Usa el mismo ecosistema Meta de Facebook Login.
2. Obtiene un token de larga duracion.
3. Obtiene el `Instagram User ID`.
4. Guarda:
   - `INSTAGRAM_ACCESS_TOKEN`
   - `INSTAGRAM_IG_USER_ID`

Nota:

- Si no defines `INSTAGRAM_ACCESS_TOKEN`, el adaptador intenta usar `FACEBOOK_PAGE_TOKEN`.
- Instagram no publica texto solo. Requiere imagen.

### Obtener Instagram Business Account ID desde la pagina

```text
GET https://graph.facebook.com/v19.0/{FACEBOOK_PAGE_ID}
    ?fields=instagram_business_account,connected_instagram_account
    &access_token={FACEBOOK_PAGE_TOKEN}
```

Usa el ID que corresponda a tu cuenta conectada.

### Flujo de publicacion usado por el modulo

1. Crear contenedor:

```text
POST https://graph.facebook.com/v19.0/{INSTAGRAM_IG_USER_ID}/media
```

2. Publicar contenedor:

```text
POST https://graph.facebook.com/v19.0/{INSTAGRAM_IG_USER_ID}/media_publish
```

## LinkedIn

### Requisitos

- App de LinkedIn con OAuth 2.0
- Token con permiso `w_organization_social` si publicas en pagina
- Rol valido en la pagina de empresa

### Flujo recomendado

1. Crear app en LinkedIn Developer Portal.
2. Configurar `redirect URI`.
3. Ejecutar OAuth 2.0 de 3 patas.
4. Guardar:
   - `LINKEDIN_ACCESS_TOKEN`
   - `LINKEDIN_ORG_ID` si publicas como empresa
   - `LINKEDIN_PERSON_URN` si publicas como perfil

### Authorization URL

```text
https://www.linkedin.com/oauth/v2/authorization
    ?response_type=code
    &client_id={CLIENT_ID}
    &redirect_uri={REDIRECT_URI}
    &scope=w_organization_social%20r_organization_social
    &state={RANDOM_STATE}
```

### Exchange code for access token

```text
POST https://www.linkedin.com/oauth/v2/accessToken
Content-Type: application/x-www-form-urlencoded

grant_type=authorization_code
&code={AUTH_CODE}
&redirect_uri={REDIRECT_URI}
&client_id={CLIENT_ID}
&client_secret={CLIENT_SECRET}
```

## Scheduler

Para publicaciones programadas:

```powershell
php artisan schedule:run
```

En produccion debe ejecutarse cada minuto.

## Referencias oficiales

- LinkedIn Posts API:
  - https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/posts-api?view=li-lms-2025-06
- LinkedIn OAuth / Getting Access:
  - https://learn.microsoft.com/en-us/linkedin/shared/authentication/getting-access
- LinkedIn Organic Posts:
  - https://learn.microsoft.com/en-us/linkedin/marketing/usecases/page-management/organic-posts-usecase
- Meta Access Tokens:
  - https://developers.facebook.com/docs/facebook-login/guides/access-tokens/
- Meta Page Tokens:
  - https://developers.facebook.com/docs/facebook-login/guides/access-tokens/#pagetokens
- Instagram Content Publishing:
  - https://developers.facebook.com/docs/instagram-platform/content-publishing
