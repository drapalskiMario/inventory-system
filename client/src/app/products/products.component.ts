import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { lastValueFrom } from 'rxjs';

@Component({
  selector: 'app-products',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './products.component.html',
  styleUrl: './products.component.css'
})
export class ProductsComponent {
  private apiUrl = 'http://localhost:3000/api/products'

  products: any[] = [];
  showingList: boolean = true;
  showingForm: boolean = false;
  newProduct: any = { id: null, name: '', description: '', price: '', stock_quantity: '' };
  isEditing: boolean = false;

  constructor(private http: HttpClient) { }

  async ngOnInit() {
    const source = this.http.get(this.apiUrl, {
      headers: { 'Accept': 'application/json' }
    });
    const { products } = await lastValueFrom(source) as any
    console.log('products :', products);
    this.products = products;
  }

  showList() {
    this.showingList = true;
    this.showingForm = false;
  }

  showForm() {
    this.showingList = false;
    this.showingForm = true;

  }

  async saveProduct() {
    if (this.isEditing) {
      const index = this.products.findIndex(c => c.id === this.newProduct.id);
      const source = this.http.put(`${this.apiUrl}/${this.newProduct.id}`, { ...this.newProduct }, {
        headers: { 'Accept': 'application/json' }
      });
      await lastValueFrom(source)
      this.products[index] = { ...this.newProduct };
    } else {
      const source = this.http.post(this.apiUrl, { ...this.newProduct }, {
        headers: { 'Accept': 'application/json' }
      });
      const res = await lastValueFrom(source) as any;
      this.newProduct.id = res.product.id;
      this.products.push(this.newProduct)
    }
    this.showList();
  }

  editProduct(product: any) {
    this.isEditing = true;
    this.newProduct = { ...product };
    this.showForm();
  }

  async deleteProduct(id: number) {
    const source = this.http.delete(`${this.apiUrl}/${id}`);
    await lastValueFrom(source)
    const index = this.products.findIndex(c => c.id === this.newProduct.id);
    this.products.splice(index, 1);
  }
}
