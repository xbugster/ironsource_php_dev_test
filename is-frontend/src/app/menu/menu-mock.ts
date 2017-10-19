import { Menu } from './menu';

export const MENUS: Menu[] = [
    { position: 0, label: 'Home', hyperlink: '/', isExactMatch: true },
    { position: 1, label: 'Packages', hyperlink: '/packages', isExactMatch: false },
    { position: 2, label: 'Offers', hyperlink: '/offers', isExactMatch: false },
    { position: 3, label: 'Generate Offers', hyperlink: '/generate_offers', isExactMatch: false },
    { position: 4, label: 'Generate packages files', hyperlink: '/generate_package_files', isExactMatch: false },
];