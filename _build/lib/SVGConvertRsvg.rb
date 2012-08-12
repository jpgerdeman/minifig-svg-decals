class SVGConvertRsvg < SVGConvert
include OSFunctions

	def generateCommand()
		cmd = "rsvg-convert --keep-aspect-ratio --format=" + @format + " --output=\"" + @outfile + "\" ";
		
		unless @width == nil		
			cmd = cmd + "--width=" + @width.to_s() + " "
		end
		
		unless @dpi == nil
			cmd = cmd + "--dpi-x=" + @dpi.to_s() + " " + "--dpi-y=" + @dpi.to_s() + " "
		end

		cmd = cmd + "\"" + @infile + "\"";		
	
		return cmd;
	end
end
