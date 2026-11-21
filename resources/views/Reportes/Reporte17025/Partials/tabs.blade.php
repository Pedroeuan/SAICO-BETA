<style>
    .report-category-tabs { 
        display:flex; 
        gap:8px; 
        flex-wrap:wrap; 
        padding:8px; 
        margin:0 auto 24px; 
        background:#edf2f7; 
        border:1px solid #dce3ec; 
        border-radius:14px; 
        width:fit-content; 
    }
    .report-category-tabs a { 
        display:flex; 
        align-items:center; 
        gap:10px; 
        padding:13px 24px; 
        border-radius:10px; 
        color:#40516a; 
        font-weight:600; 
        text-decoration:none; 
        transition:background .15s, color .15s; 
    }
    .report-category-tabs a:hover { 
        background:#dde7f2; 
        color:#135b9e; 
    }
    .report-category-tabs a.active { 
        background:var(--category-color); 
        color:#fff; 
        box-shadow:0 3px 8px #183c5926; 
    }
    .report-category-tabs a:focus-visible { 
        outline:3px solid #e6a931; 
        outline-offset:3px; 
    }
    @media(max-width:575px) 
    {
        .report-category-tabs {
            width:100%;
            } 
        .report-category-tabs a { 
            flex:1; 
            justify-content:center; 
            padding:12px; 
            }
    }
</style>
<div style="padding-top: 100px;" aria-hidden="true"><br><br></div>
<nav class="report-category-tabs" aria-label="Categoría de reportes">
    <a style="--category-color:#C04040" href="{{ route('Menu.Servicios') }}" class="{{ !in_array(request('categoria'), ['im', '17025']) ? 'active' : '' }}" @if(!in_array(request('categoria'), ['im', '17025'])) aria-current="page" @endif>
        <i class="fas fa-file-alt" aria-hidden="true"></i> PINS
    </a>
    <a style="--category-color:#0070C0" href="{{ route('Menu.Servicios', ['categoria' => 'im']) }}" class="{{ request('categoria') === 'im' ? 'active' : '' }}" @if(request('categoria') === 'im') aria-current="page" @endif>
        <i class="fas fa-microscope" aria-hidden="true"></i> IM
    </a>
    <a style="--category-color:#C04040" href="{{ route('Menu.Servicios', ['categoria' => '17025']) }}" class="{{ request('categoria') === '17025' ? 'active' : '' }}" @if(request('categoria') === '17025') aria-current="page" @endif>
        <i class="fas fa-flask" aria-hidden="true"></i> 17025
    </a>
</nav>
