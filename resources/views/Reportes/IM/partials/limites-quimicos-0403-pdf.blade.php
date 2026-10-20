{{--
    Límites teóricos de filaNorma para ambos idiomas del PDF 04_03.
    Cada referencia mantiene su color, comenzando por negro y azul; se separan con una barra.
    El respaldo Composicion permite mostrar reportes antiguos sin la lista Referencias.
--}}
@php($coloresQuimicos = ['#111111', '#1565c0', '#c62828', '#6a1b9a', '#2e7d32', '#ad5700'])
@forelse(($filaNorma['Referencias'] ?? []) as $referenciaQuimica)
    @if(!$loop->first) / @endif
    <span style="color: {{ $coloresQuimicos[($referenciaQuimica['indice'] ?? 0) % count($coloresQuimicos)] }};font-weight:bold">{{ $referenciaQuimica['valor'] ?? '' }}</span>
@empty
    {{ $filaNorma['Composicion'] ?? '' }}
@endforelse
