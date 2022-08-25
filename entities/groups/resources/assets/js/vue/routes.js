const ClassifiersPackage_Groups_Pages_Index = () => import('~vue_classifiers-package_groups/components/pages/Index');
const ClassifiersPackage_Groups_Pages_Form = () => import('~vue_classifiers-package_groups/components/pages/Form');

export default [
    {
        path: '/classifiers-package/groups',
        name: 'inetstudio.classifiers-package.groups.back.resource.index',
        components: {
            content: ClassifiersPackage_Groups_Pages_Index
        }
    },
    {
        path: '/classifiers-package/groups/create',
        name: 'inetstudio.classifiers-package.groups.back.resource.create',
        components: {
            content: ClassifiersPackage_Groups_Pages_Form
        }
    },
    {
        path: '/classifiers-package/groups/:id/edit',
        name: 'inetstudio.classifiers-package.groups.back.resource.edit',
        components: {
            content: ClassifiersPackage_Groups_Pages_Form
        },
        props: {
            default: true,
            content: route => ({ id: route.params.id })
        }
    }
];
