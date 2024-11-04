import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms'
import { HttpClient } from '@angular/common/http';
import { lastValueFrom } from 'rxjs';

@Component({
  selector: 'app-orders',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './orders.component.html',
  styleUrl: './orders.component.css'
})
export class OrdersComponent {
  private apiUrl = 'http://localhost:3000/api/orders'

  showingForm: boolean = false;
  showingSearch: boolean = false;
  newOrder: any = { customer_id: '', product_id: '', quantity: null };
  searchCode: string = '';
  orderFound: boolean = false;
  orderNotFound: boolean = false;
  orderDetails: any = {};
  orderAdded = false;
  newOrderId: number | null = null;
  errorOnSave = false;

  constructor(private http: HttpClient) { }

  showForm() {
    this.newOrderId = null;
    this.orderAdded = false;
    this.showingForm = true;
    this.showingSearch = false;
    this.orderFound = false;
    this.orderNotFound = false;
  }

  showSearch() {
    this.showingForm = false;
    this.showingSearch = true;
    this.orderFound = false;
    this.orderNotFound = false;
    this.orderAdded = false;
  }

  async addOrder() {
    try {
      const source = this.http.post(`${this.apiUrl}`, this.newOrder);
      const { order } = await lastValueFrom(source) as any;
      this.newOrderId = order.id;
      this.orderAdded = true;
      this.showingForm = false
      this.newOrder = { customer_id: '', product_id: '', quantity: null }
    } catch (error) {
      this.newOrder = { customer_id: '', product_id: '', quantity: null }
      this.showingForm = false
      this.errorOnSave = true;
    }
  }

  async searchOrder() {
    let order;
    const statusTranslate: { [key: string]: string } = {
      'new': 'Pedido criado, aguardando processamento',
      'processed': 'Pedido em processamento',
      'shipped': 'Pedido foi processado e enviado ao cliente',
      'cancelled': 'Pedido cancelado'
    }
    const source = this.http.get(`${this.apiUrl}/${this.searchCode}`);
    try {
      const { order } = await lastValueFrom(source) as any;
      this.orderDetails = order;
      this.orderDetails.total_value = Number(this.orderDetails.quantity) * Number(this.orderDetails.product.price);
      this.orderDetails.status = statusTranslate[this.orderDetails.status];
      this.orderFound = true;
      this.orderNotFound = false;
    } catch (error) {
      this.orderFound = false;
      this.orderNotFound = true;
    }
  }
}