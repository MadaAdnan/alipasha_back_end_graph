<x-web.components.layouts.layout-component>
    <x-slot name="rightSidebar">
        <x-web.components.layouts.right-side-bar-component name="name"/>
    </x-slot>

    <x-slot name="leftSidebar">

        <x-web.components.layouts.left-side-bar-component name="name" :list="$categories"/>
    </x-slot>
</x-web.components.layouts.layout-component>


