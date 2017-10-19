import {Component, OnInit} from '@angular/core';
import { MiscService } from "./misc.service"

@Component({
  selector: 'misc-actions',
  templateUrl: './misc.component.html',
  styleUrls: ['./misc.component.css'],
})
export class GenerateOffersComponent implements OnInit {
  title = 'Generate Offers';
  pleaseWaitMsg = 'Please wait while we generate offers for packages...';
  resultsNumber = 0;
  systemMessage = '';

    constructor(private miscService : MiscService) {
        this.systemMessage = this.pleaseWaitMsg;
    }

    ngOnInit() : void {
      this.callGenerateOffers();
    }

    callGenerateOffers() {
        return this.miscService.generateOffers()
            .subscribe(result => this.resultReceiver(result));
    }

    resultReceiver(result) {
        this.resultsNumber = result;
        this.systemMessage = 'Great! We just generated ' + result + ' offer+package+country compositions. '
    }
}
