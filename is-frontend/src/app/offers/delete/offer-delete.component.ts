import {Component, Input, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";

import {OffersService} from "../offers.service";

@Component({
  selector: 'offers-removal',
  templateUrl: './offer-delete.component.html'
})
export class OfferDeleteComponent implements OnInit {
  title = 'Offer removal';
  success = false;
  error = false;
  subject_id = null;
  constructor(
      private offersService : OffersService,
      private route: ActivatedRoute
  ) {
      this.success = false;
      this.error = false;
  }

  ngOnInit() {
      this.route.params.subscribe( params => {
        this.subject_id = params.id;
        this.removeOffer(params.id);
      });
  }

  removeOffer(offerId = null) {
    if (offerId == null) {
      this.error = true;
      return;
    }
    this.offersService.removeOffer(offerId).subscribe(
        result => {
            this.success = result;
            this.error = !result; },
        error => {
            this.error = true
        }
    );
  }
}
