<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Entity;

use OwenIt\Auditing\Contracts\Auditable;

interface EntryModelContract extends Auditable
{
    const ENTITY_TYPE = 'classifiers_package_entry';

    const ENTITY_DESCRIPTION = 'Classifiers entry';
}
