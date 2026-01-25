
<div class="sidebar-categories-container">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>المحادثات</span>
    </div>
    <ul class="categories-list">

        @foreach($communities as $community)
            <li class="category-item-wrapper">
                <x-components.convirsation-item-component :community="$community"/>
            </li>
        @endforeach
    </ul>
</div>

