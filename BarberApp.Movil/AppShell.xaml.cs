using BarberApp.Movil.Views;

namespace BarberApp.Movil
{
    public partial class AppShell : Shell
    {
        public AppShell()
        {
            InitializeComponent();
            Routing.RegisterRoute("register",typeof(RegisterPage));
            Routing.RegisterRoute("serviceDetail", typeof(DetailsServicePage));
            Routing.RegisterRoute("addReservation", typeof(AddReservationPage));
        }
    }
}
