using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class ServiciosPage : ContentPage
{
	readonly ServiciosViewModel vm;
	public ServiciosPage()
	{
		InitializeComponent();
		BindingContext = vm = new ServiciosViewModel();
	}
    protected override async void OnAppearing()
    {
        base.OnAppearing();
        await vm.ObtenerServicios();
    }
}