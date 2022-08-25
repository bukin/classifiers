const ClassifiersPackage_Entries_Pages_Index = () => import('~vue_classifiers-package_entries/components/pages/Index');
const ClassifiersPackage_Entries_Pages_Form = () => import('~vue_classifiers-package_entries/components/pages/Form');

export default [
    {
        path: '/classifiers-package/entries',
        name: 'inetstudio.classifiers-package.entries.back.resource.index',
        components: {
            content: ClassifiersPackage_Entries_Pages_Index
        }
    },
    {
        path: '/classifiers-package/entries/create',
        name: 'inetstudio.classifiers-package.entries.back.resource.create',
        components: {
            content: ClassifiersPackage_Entries_Pages_Form
        }
    },
    {
        path: '/classifiers-package/entries/:id/edit',
        name: 'inetstudio.classifiers-package.entries.back.resource.edit',
        components: {
            content: ClassifiersPackage_Entries_Pages_Form
        },
        props: {
            default: true,
            content: route => ({ id: route.params.id })
        }
    }
];
