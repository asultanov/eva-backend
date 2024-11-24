<div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            @if(@isset($breadcrumbs))
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item">
                            @if(isset($breadcrumb['link']))
                                <a href="{{ $breadcrumb['link'] == 'javascript:void(0)' ? $breadcrumb['link']:url($breadcrumb['link']) }}">
                                    @endif
                                    {{$breadcrumb['name']}}
                                    @if(isset($breadcrumb['link']))
                                </a>
                            @endif
                        </li>
                        @endforeach
                    </ol>
                </nav>
            @endisset
        </div>
    </div>
</div>

