<?php
namespace sportnet\model;
abstract class AbstractModel {
    /* une instance de PDO */
    protected static $db; 
    
    
    public function __get($attr_name) {
        if (property_exists( $this, $attr_name))
            return $this->$attr_name;
        $emess = __CLASS__ . ": unknown member $attr_name (__get)";
        throw new \Exception($emess);
    }
    
    public function __set($attr_name, $attr_val) {
        if (property_exists( $this, $attr_name)) 
            $this->$attr_name=$attr_val; 
        else{
            $emess = __CLASS__ . ": unknown member $attr_name (__set)";
            throw new \Exception($emess);
        }
    }
    
    public function __toString(){
        $prop = get_object_vars ($this);
        $str="";
        foreach ($prop as $name => $val){
            if( !is_array($val) ) 
                $str .= "$name : $val <br> ";
            else
                $str .= "$name : ". print_r($val, TRUE)."<br>";
        }
        return $str;
    } 


    /*
     * Mise a jour d'un enregistrement 
     *   
     * Met à jour l'état l'objet courant dans la base
     *   
     * Algorithme :
     *
     * - Préparer une requête sql update 
     * - lier les attributs aux paramettre de la requette 
     *  - executer la requêtte
     *		
     *   @return boolean
     *
     */
    
    abstract protected function update();
    
    /*
     * Insertion d'un enregistrement dans la base
     *
     * Insère les attribut de l'objet comme une nouvelle ligne dans la table
     * l'objet ne doit pas posséder un id
     *
     * Algorithme :
     * 
     * - prépare une requête d'insertion 
     * - lier les attribut aux paramètres de la requête 
     * - exécuter la requête 
     * - récupérer l'identifiant de la ligne insérée avec la méthode LastInsertId
     *   de la classe PDO
     * - enregistrer l'identifiant dans l'attribut $id 
     * 
     * @return l'id de la page de la ligne insérée ou -1 en ca d'erreur 
     * 
     */
    
    abstract protected function insert();

    /*
     * Sauvegarder un enregistrement dans la base
     *
     * Enregistre les attributs de l'objet dans la table
     *   
     * Algorithme :
     *
     * - Si l'objet possede un identifiant : 
     *     mise à jour de la ligne correspondante (update)
     * - sinon  
     * 	   insertion dans une nouvelle ligne (insert)
     *
     * - retourner un booléen vrai si l'operation a réussie ou faux sinon
     *
     *   @return boolean 
     *
     */
    
    abstract public function save();

    
    /*
     * Suppression d'un enregistrement de la base
     *
     * Supprime la ligne dans la table corrsepondant à l'objet courant
     * L'objet doit posséder un ID
     *
     * Algorithme :
     *
     * - vérifier la valeur de l'attribut id et retourner 0 si pas d'id
     * - préparer une requêtte de suppression 
     * - lier le paramètre id 
     * - exécuter la requête 
     * - retourner le nombre de ligne supprimer
     * 
     * @return integer 
     *
     */
    
    abstract public function delete();
    
    
    /*
     * Récupérer un enregistrement  
     *
     * Retrouve la ligne de la table correspondant au id passé en 
     * paramètre, retourne un objet.
     * 
     * Algorithme : 
     * 
     * - préparer une requête de sélection 
     * - lier l'id recherché au paramètre de la requête
     * - récupérer le résultat
     * - créer un objet du modèle et l'initialiser avec le résultat.  
     * - retourner l'objet ou faux si erreur 
     * 
     * @static
     * @param  integer $id  to find
     * @return renvoie un objet model ou faux
     */
    
    abstract static public function findById($id);
    
    
    /*
     * Récupérer tous les enregistrements dans la table  
     *
     * Renvoie toutes les lignes de la table sous la forme d'un tableau d'objets
     *  
     * Algorithme :
     *
     * - exécuter une requête de sélection de toutes les lignes de la table
     * - pour chaque lignes du résultat, créer un objet page, le remplir et le 
     *   stocker dans un tableau
     * - retourner le tableau
     * 
     * @static
     * @return Array renvoie un tableau d'objets modèle ou vide
     *
     */
    
    abstract static public function findAll();


}