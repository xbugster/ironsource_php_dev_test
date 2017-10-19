import { Component } from '@angular/core';
import {PackagesService} from "../packages.service";
import {NgForm} from "@angular/forms"

@Component({
  selector: 'packages-component',
  templateUrl: './package-add.component.html',
  styleUrls: ['./package-add.component.css'],
})
export class PackageAddComponent {
    title = 'Create new package';
    success = false;
    error = false;

    constructor(private packageService : PackagesService) {}

    createPackage(form : NgForm) {
        this.success = false;
        this.error = false;
        this.packageService.createPackage(JSON.stringify(form.value)).subscribe(
            result => {
                this.success = result;
                this.error = !result;
            },
            res => {
                this.error = true
            }
        );
    }
}
