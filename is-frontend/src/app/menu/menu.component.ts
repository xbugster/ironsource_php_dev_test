import { Component, OnInit } from "@angular/core"

import { Menu } from './menu';
import { MenuService } from "./menu.service"

@Component({
    selector: 'menu-component',
    templateUrl: './menu.component.html',
    styleUrls: ['./menu.component.css'],
    providers: [ MenuService ]
})

export class MenuComponent implements OnInit{
    menus: Menu[];
    constructor(private menuService: MenuService) { }

    ngOnInit(): void {
        this.getMenu();
    }

    getMenu() : void {
        this.menuService.getLinksAndLabels().then(menus => this.menus = menus);
    }
}