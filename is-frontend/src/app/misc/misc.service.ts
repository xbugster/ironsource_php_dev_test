import { Injectable } from "@angular/core"
import {HttpClient} from "@angular/common/http";
import 'rxjs/add/operator/map';

@Injectable()
export class MiscService {
    private generateOffersUrl : string = 'http://localhost:8000/map_offers';
    private generatePackageFilesUrl : string = 'http://localhost:8000/generate_packages_files';

    constructor(private http: HttpClient) {}

    generateOffers() {
        return this.http.get(this.generateOffersUrl)
            .map(response => response['data'])
    }

    generatePackageFiles() {
        return this.http.get(this.generatePackageFilesUrl)
            .map(response => response['data'])
    }
}