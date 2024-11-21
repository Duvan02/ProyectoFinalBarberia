using CommunityToolkit.Mvvm.ComponentModel;

namespace BarberApp.Movil.Models;
public class Barbero : ObservableObject
{
    public int IdEstilista { get; set; }
    public string Nombres { get; set; }
    public string Foto { get; set; }

    private bool isSelected;
    public bool IsSelected
    {
        get { return isSelected; }
        set
        {
            isSelected = value;
            OnPropertyChanged(nameof(IsSelected));
        }
    }
}
