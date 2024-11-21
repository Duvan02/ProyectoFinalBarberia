using CommunityToolkit.Mvvm.ComponentModel;

namespace BarberApp.Movil.Models;
public class Servicio : ObservableObject
{
    public int IdServicio { get; set; }
    public string Nombre { get; set; }
    public string Descripcion { get; set; }
    public int TiempoDuracion { get; set; }
    public double Precio { get; set; }
    public string Imagen { get; set; }
    private bool isSelected;

    public bool IsSelected
    {
        get { return isSelected; }
        set { isSelected = value;
            OnPropertyChanged(nameof(IsSelected));
        }
    }

}
