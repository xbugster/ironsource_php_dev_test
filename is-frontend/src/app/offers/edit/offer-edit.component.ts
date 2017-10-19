import { Component } from '@angular/core';
import {ActivatedRoute} from "@angular/router"
import {OffersService} from "../offers.service";
import {NgForm} from "@angular/forms";

@Component({
  selector: 'offers-component',
  templateUrl: './offer-edit.component.html',
  styleUrls: ['./offer-edit.component.css'],
})
export class OfferEditComponent {
    title = 'Offer Edition';
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
        private offersService : OffersService
    ) {
        this.route.params.subscribe(params => {
            this.subjectId = params.id;
            this.getPackage(params.id);
        });
    }

    updateOffer(form : NgForm) {
        this.offersService.updateOffer(JSON.stringify(form.value)).subscribe(
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
        this.offersService.getOffers(null,null, id).subscribe(
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
