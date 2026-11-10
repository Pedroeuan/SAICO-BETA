@extends('adminlte::page')
@section('title', 'Mi perfil')
@section('content_header')
<div class="profile-heading"><h1>Mi perfil</h1></div>
@endsection
@section('css')
<style>
.profile-heading{
    max-width:880px;
    margin:72px auto 0;
    padding:0 8px
}
.profile-heading h1{
    font-size:28px;
    font-weight:600;
    color:#001f3f;
    margin-bottom:24px
}
.profile-heading p{
    color:#526477;
    margin:8px 0 20px;
    font-size:16px
}
.profile-card{
    max-width:880px;
    margin:0 auto 32px;
    border:1px solid #dce4ec;
    border-top:4px solid #c82333;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 28px rgba(0,31,63,.08)
}
.profile-grid{
    display:grid;
    grid-template-columns:280px 1fr
}
.profile-preview{
    background:#001f3f;
    padding:40px 24px;
    text-align:center;
    border-right:1px solid #dce4ec;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center
}
.profile-avatar{
    width:180px;
    height:180px;
    object-fit:cover;
    border-radius:20px;
    border:6px solid #fff;
    box-shadow:0 3px 16px rgba(23,50,77,.16);
    margin-bottom:20px
}
.profile-preview strong{
    font-size:18px;
    color:#fff;
    overflow-wrap:anywhere;
    max-width:100%
}
.profile-preview p{
    color:#526477;
    font-size:14px;
    margin:8px 0 0
}
.profile-upload{
    padding:36px;
    display:flex;
    flex-direction:column;
    justify-content:center
}
.profile-upload h2{
    font-size:21px;
    font-weight:600;
    color:#17324d;
    margin-bottom:12px
}
.profile-upload 
.profile-description{
    color:#526477;
    font-size:16px;
    line-height:1.6
}
.profile-file{
    border:1px dashed #8ba6c0;
    background:#f8fafc;
    border-radius:10px;
    padding:18px;
    margin:24px 0
}
.profile-file input{
    display:block;
    width:100%;
    font-size:15px;
    color:#17324d
}
.profile-file input::file-selector-button{
    background:#001f3f;
    border:1px solid #001f3f;
    border-radius:6px;
    color:#fff;
    padding:9px 12px;
    margin-right:12px;
    cursor:pointer;
    font-weight:600
}
.profile-file input:focus-visible{
    outline:3px solid #287dc4;
    outline-offset:5px
}
.profile-file small{
    display:block;
    font-size:14px;
    line-height:1.6;
    color:#526477;
    margin-top:14px
}
.profile-save{
    padding:12px 24px;
    font-weight:600;
    border-radius:8px;
    font-size:16px;
    background:#c82333;
    border-color:#c82333;
    color:#fff
}
.profile-save:hover,.profile-save:focus{
    background:#a71d2a;
    border-color:#a71d2a;
    color:#fff
}
.profile-upload .alert{
    border-radius:8px
}
.profile-role{
    display:inline-block;
    color:#fff;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.3);
    border-radius:20px;
    padding:5px 14px;
    margin-top:12px;
    font-size:14px
}
.profile-information{
    margin:0;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px 24px
}
.profile-information div{
    min-width:0;
    border-bottom:1px solid #e8edf2;
    padding-bottom:12px
}
.profile-information dt{
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.5px;
    color:#526477;
    font-weight:600;
    margin-bottom:5px
}
.profile-information dd{
    font-size:15px;
    color:#001f3f;
    margin:0;
    overflow-wrap:anywhere
}
.profile-information 
.profile-missing{
    color:#687787;
    font-size:14px
}
.profile-photo-form{
    margin-top:24px;
    padding-top:20px;
    border-top:2px solid #edf0f4
}
.profile-photo-form 
.profile-file{
    margin:0 0 18px
}
@media(max-width:400px){
    .profile-information{
        grid-template-columns:1fr
    }
}
@media(max-width:640px){
    .profile-heading{
        margin-top:64px
        }
    .profile-grid{
        grid-template-columns:1fr
        }
    .profile-preview{
        border-right:0;
        border-bottom:1px solid #dce4ec;
        padding:28px 20px
        }
    .profile-avatar{
        width:150px;
        height:150px
        }
    .profile-upload{
        padding:24px
    }
    .profile-save{
        width:100%
        }
}
</style>
@endsection
@section('content')
<div class="card profile-card"><div class="profile-grid">
<div class="profile-preview">
<img id="profile-preview" src="{{ $user->adminlte_image() }}" alt="Mi foto de perfil" class="profile-avatar" width="180" height="180">
<strong>{{ $user->name }}</strong>
@if($user->rol)<span class="profile-role">{{ $user->rol }}</span>@endif
<span id="profile-preview-caption" class="sr-only" aria-live="polite">Foto actual de tu cuenta</span>
</div>
<div class="profile-upload">
@if(session('status') === 'profile-updated')
<div class="alert alert-success" role="status">Tu foto se actualizó correctamente.</div>
@endif
<dl class="profile-information">
    <div><dt>Nombre completo</dt><dd>{{ $user->name }}</dd></div>
    <div><dt>Correo institucional</dt><dd>{{ $user->email ?: 'Sin registrar' }}</dd></div>
    <div><dt>Rol en SAICO</dt><dd>{{ $user->rol ?: 'Sin registrar' }}</dd></div>
    <div><dt>Estado de la cuenta</dt><dd>{{ $user->Estatus ?: 'Sin registrar' }}</dd></div>
    <div><dt>Fecha de registro</dt><dd>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'Sin registrar' }}</dd></div>
</dl>
<form class="profile-photo-form" method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
@csrf
@method('patch')
<div class="form-group profile-file">
<label for="photo" class="sr-only">Selecciona tu nueva foto</label>
<input id="photo" name="photo" type="file" required accept="image/jpeg,image/png,image/webp" class="form-control-file" aria-describedby="photo-help">
<small id="photo-help" class="sr-only">JPG, PNG o WebP. Máximo 2 MB y 4096 × 4096 píxeles.</small>
@error('photo')<div class="text-danger" role="alert">{{ $message }}</div>@enderror
</div>
<button type="submit" class="btn btn-primary profile-save"><i class="fas fa-save mr-2" aria-hidden="true"></i>Guardar foto</button>
</form></div></div></div>
@endsection
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('photo');
    const preview = document.getElementById('profile-preview');
    const caption = document.getElementById('profile-preview-caption');
    const original = preview.src;
    let previewUrl;
    input.addEventListener('change', function () {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        input.setCustomValidity('');
        const file = input.files[0];
        preview.src = original;
        caption.textContent = 'Foto actual de tu cuenta';
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
            input.setCustomValidity('Selecciona una imagen JPG, PNG o WebP de hasta 2 MB.');
            input.reportValidity();
            return;
        }
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        caption.textContent = 'Vista previa · pendiente de guardar';
    });
});
</script>
@endsection
