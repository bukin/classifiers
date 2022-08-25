<?php

namespace InetStudio\ClassifiersPackage\Groups\Domain\Entity;

use OwenIt\Auditing\Contracts\Auditable;

interface GroupModelContract extends Auditable
{
    const ENTITY_TYPE = 'classifiers_package_group';

    const ENTITY_DESCRIPTION = 'Classifiers group';
}
