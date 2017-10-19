import {Component, Input, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";

import {PackagesService} from "../packages.service";


@Component({
    selector: 'package-removal',
    templateUrl: './package-delete.component.html'
})
export class PackageDeleteComponent implements OnInit {
    title = 'Package removal';
    success = false;
    error = false;
    subject_id = null;
    constructor(
        private packagesService : PackagesService,
        private route: ActivatedRoute
    ) {
        this.success = false;
        this.error = false;
    }

    ngOnInit() {
        this.route.params.subscribe( params => {
            this.subject_id = params.id;
            this.removePackage(params.id);
        });
    }

    removePackage(packageId = null) {
        if (packageId == null) {
            this.error = true;
            return;
        }
        this.packagesService.removePackage(packageId).subscribe(
            result => {
                this.success = result;
                this.error = !result; },
            error => {
                this.error = true
            }
        );
    }
}