<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You're logged in!

                    <ul class="list-group mt-3">
                        <li class="list-group-item">Username: {{ Auth::user()->username }}</li>
                        <li class="list-group-item">Email: {{ Auth::user()->email }}</li>
                        <li class="list-group-item">Referral link: 
                            <div class="tooltipper">
                                <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                <input type="button" id="ref-link" style="cursor: pointer;" class="mt-1"
                                value="{{ Auth::user()->referral_link }}" />
                            </div>
                        </li>
                        <li class="list-group-item">Referrer: {{ Auth::user()->referrer->name ?? 'Not Specified' }}</li>
                        <li class="list-group-item">Refferal count: {{ count(Auth::user()->referrals)  ?? '0' }}</li>
                    </ul>

                    @forelse($notifications as $notification)
                    <div class="alert alert-success" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                            [{{ $notification->created_at }}] User {{ $notification->data['name'] }} ({{ $notification->data['email'] }}) has just registered via your referral link.
                            </div>
                            <div class="col-sm-2">
                                <form method="POST" action="{{ route('markNotification', $notification->id) }}">
                                    @csrf
                                    <button class="btn btn-outline-success">Mark as read</button>
                                </form>
                            </div>
                        </div>                        
                    </div>

                    @if($loop->last)
                        <form method="POST" action="{{ route('markAllNotification') }}">
                            @csrf
                            <button class="btn btn-outline-info">Mark all as read</button>
                        </form>
                    @endif
                    @empty
                        There are no new notifications
                    @endforelse
                </div>
            </div>
        </div>
    </div>
<script>
    $(function(){
        
        $('#ref-link').click(function(){
            let refLink = $(this).val();
            let $temp = $("<input>");

            $("body").append($temp);
            $temp.val(refLink).select();
            document.execCommand("copy");
            $temp.remove();

            $('#myTooltip').html('Link copied!');                                 
        });
        $('.tooltipper')
        .mouseover(function(){
            $('.tooltipper .tooltiptext').css({'visibility':'visible','opacity': 1});
            $('#myTooltip').html('Copy to clipboard');
        })
        .mouseout(function(){
            $('.tooltipper .tooltiptext').css({'visibility':'visible','opacity': 0});
        });
    })
</script>
</x-app-layout>
