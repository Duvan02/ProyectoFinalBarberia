using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class ReservasPage : ContentPage
{
	readonly ReservasViewModel vm;
	public ReservasPage()
	{
		InitializeComponent();
		BindingContext = vm = new ReservasViewModel();
	}

    protected override async void OnAppearing()
    {
        base.OnAppearing();
		await vm.ObtenerReservas();
    }
}