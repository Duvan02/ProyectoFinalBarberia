using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class DetailsServicePage : ContentPage
{
    public DetailsServicePage()
    {
        InitializeComponent();
        BindingContext = new DetailServiceViewModel();
    }
}