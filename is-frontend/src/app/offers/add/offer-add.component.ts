import { Component } from '@angular/core';
import { NgForm } from "@angular/forms"
// our packages
import {OffersService} from "../offers.service";

@Component({
  selector: 'offers-component',
  templateUrl: './offer-add.component.html',
  styleUrls: ['./offer-add.component.css'],
})
export class OfferAddComponent {
  title = 'Create new offer';
  success = false;
  error = false;
  constructor(private offersService : OffersService) {}

  createOffer(form : NgForm) {
      this.success = false;
      this.error = false;
      this.offersService.createOffer(JSON.stringify(form.value)).subscribe(
          result => {
              this.success = result;
              this.error = !result; },
          res => {
              this.error = true
          }
      );
  }
}
