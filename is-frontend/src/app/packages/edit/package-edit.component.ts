import { Component } from '@angular/core';
import { ActivatedRoute } from "@angular/router"
import {NgForm} from "@angular/forms";
import {PackagesService} from "../packages.service";
import {Packages} from "../packages";

@Component({
  selector: 'packages-component',
  templateUrl: './package-edit.component.html',
  styleUrls: ['./package-edit.component.css'],
})
export class PackageEditComponent {
  title = 'Packages Edition';
  subjectId = null;
  subject = {};
  /** Update States **/
  error = false;
  success = false;
  /** Pre Load States */
  loadError = false;
  loadSuccess = true;
  constructor(
      private route: ActivatedRoute,
      private packageService : PackagesService
  ) {
      this.route.params.subscribe(params => {
          this.subjectId = params.id;
          this.getPackage(params.id);
      });
  }

  updatePackage(form : NgForm) {
    this.packageService.updatePackage(JSON.stringify(form.value)).subscribe(
        result => {
            this.success = result;
            this.error = !result;
        },
        error => {
            this.error = true;
        }
    );
  }

  getPackage(id) : void {
    this.packageService.getPackages(id).subscribe(
        result => {
            result[0].is_enabled = !!+result[0].is_enabled;
            this.subject = result[0];
            this.loadSuccess = true;
        },
        error => {
            this.loadError = true;
        }
    );
  }
}
