using BarberApp.Movil.ViewModels;

namespace BarberApp.Movil.Views;

public partial class AddReservationPage : ContentPage
{
    readonly AddReservationViewModel vm;
    public AddReservationPage()
    {
        InitializeComponent();
        BindingContext = vm = new AddReservationViewModel();
    }
    protected override async void OnAppearing()
    {
        base.OnAppearing();
        await vm.ObtenerServicios();
    }

    private async void DatePicker_DateSelected(object sender, DateChangedEventArgs e)
    {
        await vm.ObtenerBarberosDisponibles();
    }
}