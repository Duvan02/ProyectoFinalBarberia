using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;
public partial class HomePage : ContentPage
{
	readonly HomeViewModel vm;
    public HomePage()
	{
		InitializeComponent();
		BindingContext = vm = new HomeViewModel();
	}
    protected override async void OnAppearing()
    {
        base.OnAppearing();
        await vm.ObtenerServicios();
        await vm.ObtenerBarberos();
    }
}