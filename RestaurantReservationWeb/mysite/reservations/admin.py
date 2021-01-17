from django.contrib import admin
from .models import Customer, Restaurant, Reservation

# Register your models here.

admin.site.register(Customer)
admin.site.register(Restaurant)
admin.site.register(Reservation)