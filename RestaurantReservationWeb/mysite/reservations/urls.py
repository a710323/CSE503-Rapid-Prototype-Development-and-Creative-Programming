from django.urls import path
from django.contrib.auth import views as auth_views
from . import views
from .views import  ReservationDetailView, ReservationCreateQuickView, ReservationUpdateView, ReservationDeleteView
from .views import extendCreateView
#from .views import ReservationListView
#from .views import ReservationListView


# just for the test run on server
urlpatterns = [
    path('home/', views.home, name="home"),
    path('register/', views.register, name="register"),
    path('profile/', views.profile, name="profile"),
    path('login/', auth_views.LoginView.as_view(template_name = 'reservations/login.html'), name="login"),
    path('logout/', auth_views.LogoutView.as_view(template_name='reservations/logout.html'), name="logout"),
    path('restaurants/', views.restaurants, name="restaurants"),
    path('restaurant_details/<int:id>/', views.restaurant_details, name="restaurant_detail"),
    path('reservation/<int:pk>/', ReservationDetailView.as_view(), name='reservation_details'),
    path('reservation/<int:pk>/update/', ReservationUpdateView.as_view(), name='reservation_update'),
    path('reservation/<int:pk>/delete/', ReservationDeleteView.as_view(), name='reservation_delete'),
    path('reservation/new/', extendCreateView.as_view(), name='reservation_create'),
    path('reservation/quickSearch/', ReservationCreateQuickView.as_view(), name='reservation_quickSearch'),
    path('newrestaurant/', views.new_restaurant, name="new_restaurant"),
    # path('profile/<int:pk>', ReservationDetailView.as_view(), name="reservation_detail")
]