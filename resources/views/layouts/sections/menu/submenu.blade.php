<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)
            {{-- active menu method --}}

            @php
                $activeClass = null;
                $currentRouteName = Route::currentRouteName();

                if(isset($customRouteName))
                    $currentRouteName = $customRouteName;

                if (gettype($submenu['slug']) === 'array') {
                    foreach($submenu['slug'] as $slug){
                        if ($currentRouteName === $slug) {
                            $activeClass = 'active';
                        }
                    }
                }
                else{
                    if ($currentRouteName === $submenu['slug']) {
                        $activeClass = 'active';
                    }
                }

                if (isset($submenu['submenu'])) {
                    if (gettype($submenu['slug']) === 'array') {
                        foreach($submenu['slug'] as $slug){
                            if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
                                $activeClass = 'active';
                            }
                        }
                    }
                    else{
                        if (str_contains($currentRouteName,$submenu['slug']) and strpos($currentRouteName,$submenu['slug']) === 0) {
                            $activeClass = 'active';
                        }
                    }
                }
            @endphp

            @if($submenu['canDo'])
                <li class="menu-item {{$activeClass}} text-danger">
                    <a href="{{ isset($submenu['url']) ? url($submenu['url']) : 'javascript:void(0)' }}"
                       class="{{ $submenu['text'] ?? ''}} {{ isset($submenu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
                       @if (isset($submenu['target']) and !empty($submenu['target'])) target="_blank" @endif>
                        <div>{{ isset($submenu['name']) ? __($submenu['name']) : '' }}</div>
                        @isset($submenu['badge'])
                            <span class="badge bg-{{ $submenu['badge'][0] }} rounded-pill ms-auto">{{ $submenu['badge'][1] }}</span>
                        @endisset
                    </a>

                    {{-- submenu --}}
                    @if (isset($submenu['submenu']))
                        @include('layouts.sections.menu.submenu',['menu' => $submenu['submenu']])
                    @endif
                </li>
            @endif
        @endforeach
    @endif
</ul>
