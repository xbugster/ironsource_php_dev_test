import {Component, OnInit} from '@angular/core';
import { MiscService } from "./misc.service"

@Component({
  selector: 'misc-actions',
  templateUrl: './misc.component.html',
  styleUrls: ['./misc.component.css'],
})
export class GeneratePackageFilesComponent implements OnInit {
    title = 'Generate Packages Files';
    pleaseWaitMsg = 'This job is heavy and might take a few moments, Please wait while system generates offers files...';
    resultsNumber = 0;
    systemMessage = '';

    constructor(private miscService : MiscService) {
        this.systemMessage = this.pleaseWaitMsg;
    }

    ngOnInit() : void {
        this.callGeneratePackageFiles();
    }

    callGeneratePackageFiles() {
        return this.miscService.generatePackageFiles()
            .subscribe(result => this.resultReceiver(result));
    }

    resultReceiver(result) {
        this.resultsNumber = result;
        this.systemMessage = 'Great! We just generated ' + result + ' packages files.'
    }
}
