import {Component, OnInit} from '@angular/core';
import { Packages } from '../packages';
import { PackagesService } from "../packages.service";

@Component({
  selector: 'packages-component',
  templateUrl: './packages-list.component.html',
  styleUrls: ['./packages-list.component.css'],
})
export class PackagesListComponent implements OnInit {
  private results = [];
  title = 'Packages List';

  constructor(private packagesService: PackagesService) {}

  ngOnInit() : void {
    this.showPackages();
  }

  editLink(pack : Packages) {
      return "/packages/" + pack.id + "/edit";
  }

  deleteLink(pack : Packages) {
      return "/packages/" + pack.id + "/delete"
  }

  addLink() {
      return "/packages/add"
  }

  showPackages() {
    return this.packagesService.getPackages()
        .subscribe(result => this.results = result);
  }
}
