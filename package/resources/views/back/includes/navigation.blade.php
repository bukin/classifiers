<li class="{{ isActiveRoute('back.classifiers.*', 'mm-active') }}">
    <a href="#" aria-expanded="false"><i class="fa fa-list-alt"></i> <span class="nav-label">Классификаторы</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        @include('inetstudio.classifiers-package.groups::back.includes.package_navigation')
        @include('inetstudio.classifiers-package.entries::back.includes.package_navigation')
    </ul>
</li>
