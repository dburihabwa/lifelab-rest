--
--     Translation script
--
use lifemonitordb;

update Medicine set shape = case shape 
       		    	    when 'Gélules' then 'Capsule'
			    when 'Pommade' then 'Ointment'
			    when 'Capsule' then 'Pellet'
			    when 'Poudre' then 'Powder'
			    when 'Comprimés' then 'Pills'
			    when 'Crème' then 'Creme'
			    when 'Sirop' then 'Liquid'
			    when 'Plante' then 'Plant'
			    when 'Gaz' then 'Gas'
			    else shape 
			    end; 

update Medicine set howToTake = case howToTake
       		    	      	when 'Orale' then 'Oral'
				when 'Cutanée' then 'Dermal'
				when 'Auriculaire' then 'Auricular'
				when 'Intraveineuse' then 'Intravenous'	
				when 'Intramusculaire' then 'Intramuscular'
				when 'Sublinguale' then 'Sublingual'
				when 'Ophtalmique' then 'Ophtalmic'
				when 'Inhalée' then 'Inhaled'
				when 'Nasale' then 'Nasal'
				when 'Périneurale' then 'Perineural'
				when 'Endotrachéobronchique' then 'Endotracheopulmonary'
				else howToTake
				end;
