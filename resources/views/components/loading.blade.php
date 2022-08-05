<div
    wire:ignore
    style="display: none;"
    x-data="{show:false}"
    x-show="show"
    x-init="
        Livewire.hook('message.sent', () => { show = true })
        Livewire.hook('message.processed', () => { show = false })
    "
>

    <div
        class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 opacity-75 flex flex-col items-center justify-center">
        <div class="w-auto">
            <div class="cp-spinner cp-pinwheel"></div>
        </div>
    </div>

    <style>
        .cp-spinner{width:0;height:0;display:inline-block;box-sizing:border-box;position:relative}.cp-pinwheel{border-radius:50%;width:0;height:0;display:inline-block;box-sizing:border-box;border-top:solid 20px #0fd6ff;border-right:solid 20px #58bd55;border-bottom:solid 20px #eb68a1;border-left:solid 20px #f3d53f;animation:cp-pinwheel-animate 1s linear infinite}@keyframes cp-pinwheel-animate{0%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f;transform:rotate(0)}25%{border-top-color:#eb68a1;border-right-color:#f3d53f;border-bottom-color:#0fd6ff;border-left-color:#58bd55}50%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f}75%{border-top-color:#eb68a1;border-right-color:#f3d53f;border-bottom-color:#0fd6ff;border-left-color:#58bd55}100%{border-top-color:#0fd6ff;border-right-color:#58bd55;border-bottom-color:#eb68a1;border-left-color:#f3d53f;transform:rotate(360deg)}}
    </style>
</div>
