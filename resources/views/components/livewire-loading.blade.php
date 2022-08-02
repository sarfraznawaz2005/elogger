<div
    style="display: none;"
    x-data="{show:false}"
    x-show="show"
    x-init="
        Livewire.hook('message.sent', () => { show = true })
        Livewire.hook('message.processed', () => { show = false })
    "
>

    <div x-show="show" class="fixed left-4 bottom-4 z-50 overflow-hidden transform transition-all sm:max-w-lg">
        <div class="cp-spinner cp-pinwheel"></div>
    </div>

    <style>
        .cp-spinner{width:0;height:0;display:inline-block;box-sizing:border-box;position:relative}.cp-pinwheel{border-radius:50%;width:0;height:0;display:inline-block;box-sizing:border-box;border-top:solid 14px #0fd6ff;border-right:solid 14px #58bd55;border-bottom:solid 14px #eb68a1;border-left:solid 14px #f3d53f;animation:cp-pinwheel-animate 1s linear infinite}@keyframes cp-pinwheel-animate{0%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f;transform:rotate(0)}25%{border-top-color:#eb68a1;border-right-color:#f3d53f;border-bottom-color:#0fd6ff;border-left-color:#58bd55}50%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f}75%{border-top-color:#eb68a1;border-right-color:#f3d53f;border-bottom-color:#0fd6ff;border-left-color:#58bd55}100%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f;transform:rotate(360deg)}}
    </style>

</div>
